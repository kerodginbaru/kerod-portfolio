import ProjectForm from "@/components/admin/ProjectForm";

export default function CreateProjectPage() {
  return (
    <div>
      <h1 className="font-display text-3xl font-bold">New project</h1>
      <div className="mt-8">
        <ProjectForm />
      </div>
    </div>
  );
}
