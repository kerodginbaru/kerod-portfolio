"use client";

import { useState } from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { Loader2, Send } from "lucide-react";
import { submitContact } from "@/lib/api";

const schema = z.object({
  name: z.string().min(2, "Enter your name"),
  email: z.string().email("Enter a valid email"),
  phone: z.string().optional(),
  subject: z.string().min(3, "Give it a short subject"),
  message: z.string().min(10, "Say a bit more about the project"),
});

type FormValues = z.infer<typeof schema>;

export default function ContactForm() {
  const [status, setStatus] = useState<"idle" | "success" | "error">("idle");
  const [serverMessage, setServerMessage] = useState<string | null>(null);

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors, isSubmitting },
  } = useForm<FormValues>({ resolver: zodResolver(schema) });

  async function onSubmit(values: FormValues) {
    const res = await submitContact(values);
    if (res.ok) {
      setStatus("success");
      setServerMessage(res.message);
      reset();
    } else {
      setStatus("error");
      setServerMessage(res.message);
    }
  }

  const inputClass =
    "focus-ring w-full border-b border-[var(--ink)]/20 bg-transparent py-3 text-[var(--ink)] placeholder:text-[var(--muted)] focus:border-[var(--accent)]";

  return (
    <form onSubmit={handleSubmit(onSubmit)} noValidate className="grid gap-6">
      <div className="grid gap-6 md:grid-cols-2">
        <div>
          <input placeholder="Name" className={inputClass} {...register("name")} />
          {errors.name && <p className="mt-1 text-xs text-red-400">{errors.name.message}</p>}
        </div>
        <div>
          <input placeholder="Email" className={inputClass} {...register("email")} />
          {errors.email && <p className="mt-1 text-xs text-red-400">{errors.email.message}</p>}
        </div>
      </div>

      <div className="grid gap-6 md:grid-cols-2">
        <div>
          <input placeholder="Phone (optional)" className={inputClass} {...register("phone")} />
        </div>
        <div>
          <input placeholder="Subject" className={inputClass} {...register("subject")} />
          {errors.subject && <p className="mt-1 text-xs text-red-400">{errors.subject.message}</p>}
        </div>
      </div>

      <div>
        <textarea
          placeholder="Tell me about the project"
          rows={5}
          className={inputClass}
          {...register("message")}
        />
        {errors.message && <p className="mt-1 text-xs text-red-400">{errors.message.message}</p>}
      </div>

      <div className="flex items-center gap-4">
        <button
          type="submit"
          disabled={isSubmitting}
          className="focus-ring inline-flex items-center gap-2 rounded-full bg-[var(--accent)] px-7 py-3 font-mono text-sm font-medium text-[var(--bg)] transition-opacity hover:opacity-90 disabled:opacity-50"
        >
          {isSubmitting ? <Loader2 size={16} className="animate-spin" /> : <Send size={16} />}
          Send message
        </button>
        {status !== "idle" && (
          <p className={`font-mono text-sm ${status === "success" ? "text-[var(--accent-2)]" : "text-red-400"}`}>
            {serverMessage}
          </p>
        )}
      </div>
    </form>
  );
}
