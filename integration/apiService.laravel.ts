import {
  CreateProjectInput,
  CreateTaskInput,
  InviteMemberInput,
  MemberRole,
  Project,
  Task,
  TeamMember,
  Workspace,
  WorkspaceBundle,
} from '@/contracts';

export const API_MODE = process.env.NEXT_PUBLIC_API_MODE ?? 'laravel';
const BASE_URL = process.env.NEXT_PUBLIC_API_URL ?? 'http://localhost:8000/api/v1';

async function request<T>(path: string, init?: RequestInit): Promise<T> {
  const res = await fetch(`${BASE_URL}${path}`, {
    ...init,
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      ...(init?.headers ?? {}),
    },
  });

  if (!res.ok) {
    const errText = await res.text();
    throw new Error(errText || `API error ${res.status}`);
  }

  if (res.status === 204) return undefined as T;
  return (await res.json()) as T;
}

export const apiService = {
  getBundle(): Promise<WorkspaceBundle> {
    return request<WorkspaceBundle>('/bundle');
  },

  resetDemoData(): Promise<WorkspaceBundle> {
    return request<WorkspaceBundle>('/demo/reset', { method: 'POST' });
  },

  updateWorkspace(
    workspaceId: string,
    patch: Partial<Pick<Workspace, 'name' | 'industry' | 'plan' | 'accentColor'>>
  ): Promise<Workspace> {
    return request<Workspace>(`/workspaces/${workspaceId}`, {
      method: 'PATCH',
      body: JSON.stringify(patch),
    });
  },

  createProject(input: CreateProjectInput): Promise<Project> {
    return request<Project>('/projects', {
      method: 'POST',
      body: JSON.stringify(input),
    });
  },

  createTask(input: CreateTaskInput, actorId: string): Promise<Task> {
    return request<Task>('/tasks', {
      method: 'POST',
      body: JSON.stringify({ ...input, actorId }),
    });
  },

  updateTask(
    taskId: string,
    patch: Partial<Task>,
    actorId?: string,
    activityNote?: string
  ): Promise<Task> {
    return request<Task>(`/tasks/${taskId}`, {
      method: 'PATCH',
      body: JSON.stringify({ ...patch, actorId, activityNote }),
    });
  },

  deleteTask(taskId: string): Promise<void> {
    return request<void>(`/tasks/${taskId}`, { method: 'DELETE' });
  },

  addTaskComment(taskId: string, authorId: string, content: string): Promise<Task> {
    return request<Task>(`/tasks/${taskId}/comments`, {
      method: 'POST',
      body: JSON.stringify({ authorId, content }),
    });
  },

  inviteMember(input: InviteMemberInput): Promise<TeamMember> {
    return request<TeamMember>('/members', {
      method: 'POST',
      body: JSON.stringify(input),
    });
  },

  updateMemberRole(memberId: string, role: MemberRole): Promise<TeamMember> {
    return request<TeamMember>(`/members/${memberId}/role`, {
      method: 'PATCH',
      body: JSON.stringify({ role }),
    });
  },
};
